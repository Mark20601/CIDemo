<?php
require __DIR__ . '/vendor/autoload.php';

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

$registry = new CollectorRegistry(new InMemory());

// This tracks a basic "counter" for page views
$counter = $registry->getOrRegisterCounter('web_project', 'request_count', 'Total HTTP Requests', ['method', 'endpoint']);
$counter->incBy(1, ['GET', '/']);

// Render the metrics for Prometheus to scrape
$renderer = new RenderTextFormat();
echo $renderer->render($registry->getMetricFamilySamples());