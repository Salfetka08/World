<?php
// World/app/Console/Commands/KafkaConsumeCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RdKafka\Conf;
use RdKafka\Consumer as KafkaConsumer;
use RdKafka\ConsumerTopic;
use App\Application\Services\WorldService;
use App\Http\Requests\GetCurrentWorldRequest;
use Illuminate\Support\Facades\Log;

class KafkaConsumeCommand extends Command
{
    protected $signature = 'kafka:consume';
    protected $description = 'Consume messages from Kafka';

    private WorldService $worldService;
    private bool $running = true;
    private $producer;

    public function __construct(WorldService $worldService)
    {
        parent::__construct();
        $this->worldService = $worldService;
    }

    public function handle(): void
    {
        $this->info('Starting Kafka consumer...');

        // Инициализируем продюсера для ответов
        $this->initProducer();

        // Настройка Consumer
        $conf = new Conf();
        $conf->set('metadata.broker.list', env('KAFKA_BROKERS', 'kafka:9092'));
        $conf->set('group.id', 'world_service_group');
        $conf->set('enable.auto.commit', 'false');
        $conf->set('auto.offset.reset', 'earliest');

        // Обработка сигналов для graceful shutdown
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGTERM, [$this, 'shutdown']);
            pcntl_signal(SIGINT, [$this, 'shutdown']);
        }

        $consumer = new KafkaConsumer($conf);

        // Подписываемся на топик (альтернативный способ)
        $topic = $consumer->newTopic(env('KAFKA_TOPIC_REQUEST', 'client_to_world'));
        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

        $this->info('Waiting for messages...');

        while ($this->running) {
            try {
                // Получаем сообщение с таймаутом 1000ms
                $message = $topic->consume(0, 1000);

                if ($message === null) {
                    continue;
                }

                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        $this->handleMessage($message);
                        $topic->offsetStore($message->offset);
                        break;
                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        // Нет новых сообщений
                        break;
                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        // Таймаут
                        break;
                    default:
                        $this->error('Error: ' . $message->errstr());
                        Log::error('Kafka consumer error: ' . $message->errstr());
                        break;
                }

                if (function_exists('pcntl_signal_dispatch')) {
                    pcntl_signal_dispatch();
                }
            } catch (\Exception $e) {
                Log::error('Consumer error: ' . $e->getMessage());
                $this->error('Error: ' . $e->getMessage());
                sleep(1);
            }
        }

        $topic->consumeStop(0);
        $consumer->close();
        $this->info('Consumer stopped.');
    }

    private function initProducer(): void
    {
        $conf = new Conf();
        $conf->set('metadata.broker.list', env('KAFKA_BROKERS', 'kafka:9092'));
        $conf->set('acks', 'all');
        $this->producer = new \RdKafka\Producer($conf);
    }

    private function handleMessage(\RdKafka\Message $message): void
    {
        $this->info('Received message: ' . $message->payload);

        try {
            $data = json_decode($message->payload, true, flags: JSON_THROW_ON_ERROR);

            // Проверяем структуру сообщения
            if (!isset($data['correlation_id'], $data['action'], $data['payload'])) {
                Log::warning('Invalid message format', ['message' => $data]);
                return;
            }

            // Обрабатываем только нужное действие
            if ($data['action'] !== 'get_current_world') {
                Log::info('Unknown action: ' . $data['action']);
                return;
            }

            // Вызываем внутренний сервис и получаем ответ
            $responseData = $this->processRequest($data['payload']);

            // Отправляем ответ
            $this->sendResponse($data['correlation_id'], $responseData);

            $this->info('Message processed successfully. Correlation ID: ' . $data['correlation_id']);

        } catch (\JsonException $e) {
            Log::error('JSON decode error: ' . $e->getMessage());
            $this->sendErrorResponse($data['correlation_id'] ?? 'unknown', 'Invalid JSON format');
        } catch (\Exception $e) {
            Log::error('Message handling error: ' . $e->getMessage());
            $this->sendErrorResponse($data['correlation_id'] ?? 'unknown', $e->getMessage());
        }
    }

    private function processRequest(array $payload): array
    {
        // Создаем DTO из payload
        $request = new GetCurrentWorldRequest();
        $request->userId = $payload['user_id'] ?? null;
        $request->latitude = $payload['latitude'] ?? 0;
        $request->longitude = $payload['longitude'] ?? 0;

        // Валидация
        if (!$request->userId || !$request->latitude || !$request->longitude) {
            throw new \InvalidArgumentException('Missing required fields: user_id, latitude, longitude');
        }

        // Вызываем сервис (возвращает CurrentWorldModel)
        $currentWorld = $this->worldService->getCurrentWorld($request);

        // Формируем ответ в том же формате, что и CurrentWorldResponse
        return [
            'userId' => $currentWorld->userId,
            'weather' => $currentWorld->environmentDataModel->toArray(),
            'dayTime' => $currentWorld->dayTime,
            'season' => $currentWorld->season,
            'updatedAt' => $currentWorld->updatedAt,
            'entertainment' => $currentWorld->entertainment,
        ];
    }

    private function sendResponse(string $correlationId, array $data): void
    {
        $topic = $this->producer->newTopic(env('KAFKA_TOPIC_RESPONSE', 'world_to_client'));

        $response = [
            'correlation_id' => $correlationId,
            'status' => 'success',
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ];

        $message = json_encode($response);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $this->producer->flush(3000);

        Log::info('Response sent', ['correlation_id' => $correlationId]);
    }

    private function sendErrorResponse(string $correlationId, string $error): void
    {
        $topic = $this->producer->newTopic(env('KAFKA_TOPIC_RESPONSE', 'world_to_client'));

        $response = [
            'correlation_id' => $correlationId,
            'status' => 'error',
            'error' => $error,
            'timestamp' => now()->toIso8601String()
        ];

        $message = json_encode($response);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $this->producer->flush(3000);

        Log::error('Error response sent', ['correlation_id' => $correlationId, 'error' => $error]);
    }

    public function shutdown(int $signal): void
    {
        $this->info('Shutting down...');
        $this->running = false;
    }
}
