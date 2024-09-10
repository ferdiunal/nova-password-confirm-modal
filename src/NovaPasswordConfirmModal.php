<?php

namespace Ferdiunal\NovaPasswordConfirmModal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

class NovaPasswordConfirmModal extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-password-confirm-modal';

    /** @var callable */
    public $showResolveCallback;

    public $readResolveCallback;

    public int $countdown = 30;

    public bool $enableCountdown = false;

    public int $maskIndentFromStart = 2;

    public int $maskIndentFromEnd = 2;

    public string $maskChar = '*';

    public bool $showLast = false;

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->displayUsing(function ($value) {
            return $this->hideValue($value ?? '');
        });

        $this->resolveUsing(function ($value) {
            return $this->hideValue($value ?? '');
        });

        $this
            ->enableCountdown()
            ->countdown(15)
            ->showResolve(function (string $resource, string $resourceId, string $attribute) {
                $uniqueId = Str::uuid();
                Cache::put($uniqueId, encrypt([Auth::id(), $resource, $resourceId, $attribute, $this->resource[$this->attribute]]), now()->addSeconds(30));

                return response(route('nova-password-confirm-modal.show', compact('resource', 'resourceId', 'attribute', 'uniqueId')));
            })
            ->readResolve(function (string $resource, string $resourceId, string $attribute, string $value) {
                if ($data = cache($value)) {
                    Cache::forget($value);
                    [$id, $_resource, $_resourceId, $_attribute, $_value] = decrypt($data);

                    if ($id === Auth::id() && $resource === $_resource && $_resourceId === $resourceId && $attribute === $_attribute) {
                        $_data = bin2hex($_value);
                        $_length = strlen($_data);
                        $firstBlock = str($_data)->substr(0, $_length / 2);
                        $lastBlock = str($_data)->substr($_length / 2, $_length);

                        return response("$lastBlock$firstBlock");
                    }
                }

                abort(404);
            });
    }

    public function showResolve(callable $callback): self
    {
        $this->showResolveCallback = $callback;

        return $this;
    }

    public function readResolve(callable $callback): self
    {
        $this->readResolveCallback = $callback;

        return $this;
    }

    private function hideValue(string $value): string
    {
        $valueLength = strlen($value);
        $maskLength = $valueLength - $this->maskIndentFromStart - $this->maskIndentFromEnd;

        if ($maskLength <= 0) {
            return $value;
        }

        $maskedPart = str_repeat($this->maskChar, $maskLength);

        $startPart = $this->maskIndentFromStart > 0 ? substr($value, 0, $this->maskIndentFromStart) : '';
        $endPart = $this->maskIndentFromEnd > 0 ? substr($value, -$this->maskIndentFromEnd) : '';

        return $startPart.$maskedPart.$endPart;
    }

    public function maskChar(string $char = '*'): self
    {
        $this->maskChar = $char;

        return $this;
    }

    public function maskIndent(
        $start = 2,
        $end = 2,
    ) {
        if ($start < 0 || $end < 0) {
            throw new \InvalidArgumentException('Mask indent must be greater than or equal to 0');
        }

        $this->maskIndentFromStart = $start;
        $this->maskIndentFromEnd = $end;

        return $this;
    }

    public function countdown(int $countdown = 30): self
    {
        $this->countdown = $countdown;

        $this->withMeta([
            'countdown' => $countdown,
        ]);

        return $this;
    }

    public function enableCountdown(bool $enableCountdown = true): self
    {
        $this->enableCountdown = $enableCountdown;

        $this->withMeta([
            'enableCountdown' => $enableCountdown,
        ]);

        return $this;
    }

    public function lockField(bool $lockField = true): self
    {
        $this
            ->readonly()
            ->withMeta([
                'disabled' => true,
            ]);

        return $this;
    }
}
