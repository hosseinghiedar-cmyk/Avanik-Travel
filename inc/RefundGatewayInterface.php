<?php
namespace Avanik;
interface RefundGatewayInterface { public function supports(array $refund): bool; public function execute(array $refund): array; }
