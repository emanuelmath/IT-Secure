<div align="center">

# 🛡️ IT-Secure
### Sistema de Control de Inventario y Mantenimiento para Centros de Cómputo

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Estado](https://img.shields.io/badge/Estado-En%20Desarrollo-success?style=for-the-badge)

</div>

---

## 📋 Sobre el Proyecto

**IT-Secure** es una plataforma web diseñada para centralizar y asegurar la gestión de activos tecnológicos en centros de cómputo distribuidos por sedes. Permite llevar un control exacto del inventario y dar seguimiento al historial de mantenimientos preventivos y correctivos de cada equipo, garantizando que cada responsable gestione únicamente los centros que tiene asignados.

---

## ✨ Características Principales

### 🖥️ Gestión de Inventario y Trazabilidad
* **Inventario por Sede y Centro:** Control detallado de los equipos (computadoras, laptops, routers, periféricos) asignados a cada centro de cómputo.
* **Historial de Traslados:** Registro auditable de movimientos de equipos entre distintos centros o sedes, detallando fecha, hora y responsable del traslado.

### 🛠️ Mantenimiento y Alertas
* **Expediente Directo por Equipo:** Bitácora simplificada para registrar limpiezas, revisiones y reparaciones directamente en la hoja de vida del activo.
* **Avisos Preventivos:** Sistema de alertas automáticas que notifica cuándo un equipo requiere mantenimiento preventivo según la fecha de su última intervención.

### 📊 Reportes y Toma de Decisiones
* **Panel Estadístico:** Gráficas interactivas sobre la recurrencia de fallos para identificar equipos obsoletos y facilitar decisiones de baja definitiva.
* **Reportes Operativos:** Generación de resúmenes de estado por centro de cómputo (equipos operativos, en mantenimiento o fuera de servicio).

### 🔒 Seguridad y Auditoría
* **Aislamiento Estricto por Sede (Global Scopes):** Restricción a nivel de base de datos que garantiza que cada encargado visualice y opere exclusivamente sobre los centros asignados a su perfil, bloqueando accesos forzados por URL.
* **Borrado Seguro (Soft Deletes):** Los equipos y registros eliminados se ocultan del sistema operativo sin borrarse físicamente de la base de datos, conservando el rastro de auditoría de quién realizó la acción.

---

## 🚀 Sobre el proyecto

Es un proyecto universitario de cual, en la cátedra se está viendo MVC con Laravel.

