<?php

namespace App\Notifications;

use App\Models\Material;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notificación automática cuando un material cae por debajo de su
 * stock mínimo. Se envía por el canal de base de datos (para la
 * campanita del panel) y por correo si el usuario lo tiene configurado.
 */
class StockBajoNotification extends Notification
{
    use Queueable;

    public function __construct(protected Material $material)
    {
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'titulo' => 'Stock bajo',
            'mensaje' => "El material \"{$this->material->nombre}\" está por debajo del stock mínimo ({$this->material->stock} / {$this->material->stock_minimo}).",
            'material_id' => $this->material->id,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Alerta de stock bajo: '.$this->material->nombre)
            ->line("El material \"{$this->material->nombre}\" está por debajo de su stock mínimo.")
            ->line("Stock actual: {$this->material->stock} {$this->material->unidad}")
            ->line("Stock mínimo: {$this->material->stock_minimo} {$this->material->unidad}")
            ->action('Ver en Materiales', url('/materiales'));
    }
}
