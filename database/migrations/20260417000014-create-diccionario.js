'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // ── ROLES ──────────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE roles
        MODIFY COLUMN id          INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol',
        MODIFY COLUMN nombre      VARCHAR(255) NOT NULL                COMMENT 'Nombre del rol: Asegurado, Ajustador o Supervisor',
        MODIFY COLUMN descripcion TEXT         NULL                    COMMENT 'Descripción de las capacidades y restricciones del rol'
    `);

    // ── USUARIOS ───────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE usuarios
        MODIFY COLUMN id               INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario en el sistema',
        MODIFY COLUMN nombre           VARCHAR(255) NOT NULL                COMMENT 'Nombre(s) del usuario',
        MODIFY COLUMN apellidos        VARCHAR(255) NOT NULL                COMMENT 'Apellidos del usuario',
        MODIFY COLUMN fecha_nacimiento DATE         NOT NULL                COMMENT 'Fecha de nacimiento; se valida que el usuario sea mayor de 18 años',
        MODIFY COLUMN foto             VARCHAR(255) NULL                    COMMENT 'Ruta relativa a la foto de perfil del usuario',
        MODIFY COLUMN genero           VARCHAR(255) NOT NULL                COMMENT 'Género del usuario',
        MODIFY COLUMN email            VARCHAR(255) NOT NULL                COMMENT 'Correo electrónico único; se usa como credencial de acceso',
        MODIFY COLUMN password         VARCHAR(255) NOT NULL                COMMENT 'Contraseña almacenada con hash bcrypt',
        MODIFY COLUMN alias            VARCHAR(255) NOT NULL                COMMENT 'Nombre de usuario visible dentro de la plataforma',
        MODIFY COLUMN rol_id           INT          NOT NULL                COMMENT 'FK a roles; define si el usuario es Asegurado (1), Ajustador (2) o Supervisor (3)'
    `);

    // ── PERFILES_ASEGURADOS ────────────────────────────────────────────────
    await q(`
      ALTER TABLE perfiles_asegurados
        MODIFY COLUMN usuario_id            INT  NOT NULL COMMENT 'FK y PK al usuario; relación 1-1 con la tabla usuarios',
        MODIFY COLUMN rfc                   VARCHAR(255) NOT NULL COMMENT 'Registro Federal de Contribuyentes del asegurado',
        MODIFY COLUMN licencia_conducir     VARCHAR(255) NOT NULL COMMENT 'Número de licencia de conducir del asegurado',
        MODIFY COLUMN direccion_facturacion TEXT         NOT NULL COMMENT 'Dirección fiscal para emisión de facturas y cobro de primas'
    `);

    // ── PERFILES_EMPLEADOS ─────────────────────────────────────────────────
    await q(`
      ALTER TABLE perfiles_empleados
        MODIFY COLUMN usuario_id      INT          NOT NULL COMMENT 'FK y PK al usuario; relación 1-1 con la tabla usuarios',
        MODIFY COLUMN numero_empleado VARCHAR(255) NOT NULL COMMENT 'Número de nómina o identificador interno asignado por la empresa',
        MODIFY COLUMN zona_cobertura  VARCHAR(255) NOT NULL COMMENT 'Zona geográfica de cobertura asignada al empleado (ajustador o supervisor)'
    `);

    // ── COMPANIAS_SEGUROS ──────────────────────────────────────────────────
    await q(`
      ALTER TABLE companias_seguros
        MODIFY COLUMN id               INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la compañía aseguradora',
        MODIFY COLUMN nombre_comercial VARCHAR(255) NOT NULL                COMMENT 'Nombre comercial de la aseguradora (ej. GNP Seguros, AXA)',
        MODIFY COLUMN razon_social     VARCHAR(255) NOT NULL                COMMENT 'Razón social legal registrada ante el SAT',
        MODIFY COLUMN rfc              VARCHAR(255) NOT NULL                COMMENT 'RFC de la compañía aseguradora',
        MODIFY COLUMN telefono_cabina  VARCHAR(255) NOT NULL                COMMENT 'Teléfono de cabina de atención al cliente de la aseguradora'
    `);

    // ── SEGUROS ────────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE seguros
        MODIFY COLUMN id                   INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del producto de seguro',
        MODIFY COLUMN compania_id          INT          NOT NULL                COMMENT 'FK a la compañía aseguradora que comercializa el seguro',
        MODIFY COLUMN nombre_seguro        VARCHAR(255) NOT NULL                COMMENT 'Nombre comercial del plan o producto de seguro',
        MODIFY COLUMN nivel                VARCHAR(255) NOT NULL                COMMENT 'Nivel de cobertura: básico, intermedio o amplio',
        MODIFY COLUMN suma_asegurada       VARCHAR(255) NOT NULL                COMMENT 'Valor máximo cubierto por la póliza ante un siniestro',
        MODIFY COLUMN deducible_porcentaje VARCHAR(255) NOT NULL                COMMENT 'Porcentaje del deducible que debe pagar el asegurado al reclamar',
        MODIFY COLUMN descripcion_cobertura TEXT        NULL                    COMMENT 'Descripción detallada de coberturas, exclusiones y condiciones del seguro'
    `);

    // ── VEHICULOS ──────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE vehiculos
        MODIFY COLUMN id               INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del vehículo en el catálogo',
        MODIFY COLUMN marca            VARCHAR(255) NOT NULL                COMMENT 'Marca del fabricante del vehículo (ej. Toyota, Nissan, Ford)',
        MODIFY COLUMN modelo           VARCHAR(255) NOT NULL                COMMENT 'Nombre del modelo del vehículo (ej. Corolla, Sentra, F-150)',
        MODIFY COLUMN anio             INT          NOT NULL                COMMENT 'Año de fabricación del vehículo',
        MODIFY COLUMN version          VARCHAR(255) NOT NULL                COMMENT 'Versión o trim del modelo (ej. LE, Sport, Limited, XLE)',
        MODIFY COLUMN tipo_vehiculo    VARCHAR(255) NOT NULL                COMMENT 'Categoría del vehículo: sedán, SUV, pickup, hatchback, etc.',
        MODIFY COLUMN numero_pasajeros INT          NOT NULL                COMMENT 'Capacidad máxima de pasajeros del vehículo según especificación del fabricante',
        MODIFY COLUMN cilindros        INT          NOT NULL                COMMENT 'Número de cilindros del motor del vehículo',
        MODIFY COLUMN status           TINYINT(1)   NOT NULL DEFAULT 1      COMMENT 'Estado del registro en catálogo: 1 = activo, 0 = inactivo'
    `);

    // ── CATALOGO_ESTATUS_SINIESTROS ────────────────────────────────────────
    await q(`
      ALTER TABLE catalogo_estatus_siniestros
        MODIFY COLUMN id                  INT         NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del estado del siniestro',
        MODIFY COLUMN clave               VARCHAR(50) NOT NULL                COMMENT 'Clave interna del estado (ej. RECHAZADO, ACEPTADO, PERDIDA_TOTAL)',
        MODIFY COLUMN descripcion_interna VARCHAR(255) NOT NULL               COMMENT 'Descripción del estado legible para el personal interno',
        MODIFY COLUMN color_ui            VARCHAR(50) NULL                    COMMENT 'Color hexadecimal o clase CSS para representar el estado en la interfaz',
        MODIFY COLUMN es_terminal         TINYINT(1)  NULL    DEFAULT 0       COMMENT 'Indica si el estado es final y no permite más transiciones: 1 = sí, 0 = no',
        MODIFY COLUMN orden_flujo         TINYINT     NULL                    COMMENT 'Número de orden del estado dentro del flujo de proceso del siniestro'
    `);

    // ── POLIZAS ────────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE polizas
        MODIFY COLUMN id                   INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la póliza',
        MODIFY COLUMN usuario_id           INT          NOT NULL                COMMENT 'FK al asegurado titular de la póliza',
        MODIFY COLUMN seguro_id            INT          NOT NULL                COMMENT 'FK al producto de seguro contratado',
        MODIFY COLUMN numero_poliza        VARCHAR(255) NOT NULL                COMMENT 'Folio alfanumérico único que identifica la póliza ante la aseguradora',
        MODIFY COLUMN placas               VARCHAR(255) NOT NULL                COMMENT 'Número de placas del vehículo amparado por la póliza',
        MODIFY COLUMN fecha_inicio         DATE         NOT NULL                COMMENT 'Fecha de inicio de vigencia de la póliza',
        MODIFY COLUMN fecha_fin            DATE         NOT NULL                COMMENT 'Fecha de vencimiento; pasada esta fecha la póliza queda inactiva',
        MODIFY COLUMN estatus_poliza       VARCHAR(255) NOT NULL                COMMENT 'Estado actual de la póliza: activa, vencida o cancelada',
        MODIFY COLUMN catalogo_vehiculo_id INT          NOT NULL                COMMENT 'FK al vehículo del catálogo registrado en la póliza'
    `);

    // ── SINIESTROS ─────────────────────────────────────────────────────────
    await q(`
      ALTER TABLE siniestros
        MODIFY COLUMN id                    INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del siniestro',
        MODIFY COLUMN poliza_id             INT          NOT NULL                COMMENT 'FK a la póliza del vehículo involucrado en el siniestro',
        MODIFY COLUMN ajustador_id          INT          NOT NULL                COMMENT 'FK al empleado ajustador que levantó el reporte en sitio',
        MODIFY COLUMN supervisor_id         INT          NULL                    COMMENT 'FK al supervisor asignado para revisión y emisión de dictamen final',
        MODIFY COLUMN numero_reporte        INT          NOT NULL                COMMENT 'Folio consecutivo del reporte; se genera automáticamente por trigger',
        MODIFY COLUMN fecha_hora_siniestro  DATETIME     NULL                    COMMENT 'Fecha y hora exacta en que ocurrió el siniestro',
        MODIFY COLUMN latitud               VARCHAR(255) NOT NULL                COMMENT 'Coordenada de latitud GPS del lugar donde ocurrió el siniestro',
        MODIFY COLUMN longitud              VARCHAR(255) NOT NULL                COMMENT 'Coordenada de longitud GPS del lugar donde ocurrió el siniestro',
        MODIFY COLUMN conductor_momento     VARCHAR(255) NULL                    COMMENT 'Nombre de la persona que conducía el vehículo al momento del siniestro',
        MODIFY COLUMN descripcion_hechos    TEXT         NULL                    COMMENT 'Narrativa detallada de cómo ocurrió el siniestro redactada por el ajustador',
        MODIFY COLUMN dictamen_ajustador    VARCHAR(255) NULL                    COMMENT 'Dictamen técnico preliminar emitido por el ajustador tras el levantamiento',
        MODIFY COLUMN presupuesto_reparacion VARCHAR(255) NULL                   COMMENT 'Monto estimado en pesos para la reparación del vehículo siniestrado',
        MODIFY COLUMN estatus_id            INT          NOT NULL                COMMENT 'FK al estado actual del siniestro en el catálogo de estatus'
    `);

    // ── EVIDENCIAS_SINIESTRO ───────────────────────────────────────────────
    await q(`
      ALTER TABLE evidencias_siniestro
        MODIFY COLUMN id                 INT          NOT NULL AUTO_INCREMENT                COMMENT 'Identificador único del archivo de evidencia',
        MODIFY COLUMN siniestro_id       INT          NOT NULL                               COMMENT 'FK al siniestro al que pertenece esta evidencia',
        MODIFY COLUMN ajustador_id       INT          NOT NULL                               COMMENT 'FK al ajustador que cargó el archivo desde el sitio del siniestro',
        MODIFY COLUMN archivo_multimedia LONGBLOB     NOT NULL                               COMMENT 'Contenido binario del archivo multimedia (imagen obligatoria, video opcional)',
        MODIFY COLUMN nombre_archivo     VARCHAR(255) NOT NULL                               COMMENT 'Nombre original del archivo tal como fue subido por el ajustador',
        MODIFY COLUMN tipo_mime          VARCHAR(100) NOT NULL                               COMMENT 'Tipo MIME del archivo (ej. image/jpeg, image/png, video/mp4)',
        MODIFY COLUMN tipo_evidencia     VARCHAR(50)  NOT NULL                               COMMENT 'Categoría de la evidencia: foto_daño, foto_escena, foto_placa, video',
        MODIFY COLUMN fecha_subida       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP     COMMENT 'Fecha y hora en que el ajustador subió el archivo al sistema'
    `);

    // ── TERCEROS_INVOLUCRADOS ──────────────────────────────────────────────
    await q(`
      ALTER TABLE terceros_involucrados
        MODIFY COLUMN id                  INT          NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tercero involucrado en el siniestro',
        MODIFY COLUMN siniestro_id        INT          NOT NULL                COMMENT 'FK al siniestro donde participó el tercero',
        MODIFY COLUMN marca_tercero       VARCHAR(255) NOT NULL                COMMENT 'Marca del vehículo del tercero involucrado',
        MODIFY COLUMN modelo_tercero      VARCHAR(255) NOT NULL                COMMENT 'Modelo del vehículo del tercero involucrado',
        MODIFY COLUMN placas_tercero      VARCHAR(255) NOT NULL                COMMENT 'Número de placas del vehículo del tercero involucrado',
        MODIFY COLUMN aseguradora_tercero VARCHAR(255) NULL                    COMMENT 'Nombre de la aseguradora del tercero, si cuenta con cobertura',
        MODIFY COLUMN descripcion_danos   TEXT         NULL                    COMMENT 'Descripción de los daños causados al vehículo o propiedad del tercero'
    `);

    // ── SEGUIMIENTO_SINIESTROS ─────────────────────────────────────────────
    await q(`
      ALTER TABLE seguimiento_siniestros
        MODIFY COLUMN id                INT      NOT NULL AUTO_INCREMENT             COMMENT 'Identificador único del movimiento en la bitácora',
        MODIFY COLUMN siniestro_id      INT      NOT NULL                            COMMENT 'FK al siniestro al que pertenece este movimiento de seguimiento',
        MODIFY COLUMN usuario_id        INT      NOT NULL                            COMMENT 'FK al usuario que generó el cambio de estado o comentario',
        MODIFY COLUMN estatus_nuevo_id  INT      NOT NULL                            COMMENT 'FK al nuevo estado asignado al siniestro en este movimiento',
        MODIFY COLUMN comentario_publico TEXT    NULL                                COMMENT 'Mensaje visible para el asegurado; usado para comunicar el avance',
        MODIFY COLUMN notas_internas    TEXT     NULL                                COMMENT 'Notas privadas visibles solo para ajustadores y supervisores',
        MODIFY COLUMN fecha_movimiento  DATETIME NULL    DEFAULT CURRENT_TIMESTAMP   COMMENT 'Fecha y hora en que se registró este movimiento en la bitácora'
    `);

    // ── VIEW: diccionario ──────────────────────────────────────────────────
    await q(`
      CREATE OR REPLACE VIEW diccionario AS
      SELECT DISTINCT
          t.table_name,
          c.ordinal_position,
          (CASE
              WHEN t.table_type = 'BASE TABLE' THEN 'tabla'
              WHEN t.table_type = 'VIEW'       THEN 'vista'
              ELSE t.table_type
          END) AS table_type,
          c.column_name,
          c.column_type,
          c.column_default,
          c.column_key,
          c.is_nullable,
          c.extra,
          c.column_comment
      FROM information_schema.tables AS t
      INNER JOIN information_schema.columns AS c
          ON  t.table_name   = c.table_name
          AND t.table_schema = c.table_schema
      WHERE t.table_type IN ('BASE TABLE', 'VIEW')
        AND t.table_schema = DATABASE()
      ORDER BY t.table_name, c.ordinal_position
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('DROP VIEW IF EXISTS diccionario');
  }
};
