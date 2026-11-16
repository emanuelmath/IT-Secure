<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    public const UPDATED_AT = null;

    protected $fillable = [
        'usuario_id',
        'usuario_nombre',
        'evento',
        'modulo',
        'registro_id',
        'descripcion',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const eventos = [
        'LOGIN_OK',
        'LOGIN_FALLIDO',
        'LOGOUT',
        'CREADO',
        'ACTUALIZADO',
        'ELIMINADO',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public static function registrar(
        string|array $moduloOArray,
        ?string $evento = null,
        ?string $descripcion = null,
        ?int $registroId = null,
        ?int $usuarioId = null,
        ?string $usuarioNombre = null
    ): self {
        if (is_array($moduloOArray)) {
            return self::create($moduloOArray);
        }

        $eventoNormalizado = match (mb_strtoupper(trim((string) $evento))) {
            'LOGIN', 'LOGIN_OK'                                     => self::eventos[0], // LOGIN_OK
            'LOGIN_FALLIDO', 'ERROR_LOGIN'                          => self::eventos[1], // LOGIN_FALLIDO
            'LOGOUT', 'SALIDA'                                      => self::eventos[2], // LOGOUT
            'CREAR', 'CREADO', 'INSERTAR', 'MANTENIMIENTO', 'TRASLADO' => self::eventos[3], // CREADO
            'ACTUALIZAR', 'ACTUALIZADO', 'EDITAR', 'ACTIVAR', 'PASSWORD' => self::eventos[4], // ACTUALIZADO
            'ELIMINAR', 'ELIMINADO', 'INHABILITAR', 'BAJA'          => self::eventos[5], // ELIMINADO
            default                                                 => mb_strtoupper((string) $evento),
        };

        $user = Auth::user();

        return self::create([
            'usuario_id'     => $usuarioId ?? $user?->id,
            'usuario_nombre' => $usuarioNombre ?? ($user?->email ?? $user?->nombre ?? 'Sistema'),
            'evento'         => $eventoNormalizado,
            'modulo'         => $moduloOArray,
            'registro_id'    => $registroId,
            'descripcion'    => $descripcion,
            'ip_address'     => request()?->ip(),
        ]);
    }
}
