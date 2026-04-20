'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_evidencia_siniestro`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_tercero_involucrado`);

    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_siniestro(
        IN p_poliza_id             INT,
        IN p_ajustador_id          INT,
        IN p_fecha_hora_siniestro  DATETIME,
        IN p_ubicacion             VARCHAR(255),
        IN p_conductor_momento     VARCHAR(150),
        IN p_descripcion_hechos    TEXT,
        IN p_dictamen_estatus_id   INT,
        IN p_presupuesto           DECIMAL(12,2)
      )
      BEGIN
        DECLARE v_num_reporte VARCHAR(30);
        SET v_num_reporte = CONCAT('SIN-', YEAR(NOW()), '-', LPAD(FLOOR(RAND() * 999999), 6, '0'));

        INSERT INTO siniestros (
          poliza_id, ajustador_id, numero_reporte,
          fecha_hora_siniestro, latitud, longitud,
          conductor_momento, descripcion_hechos,
          dictamen_ajustador, presupuesto_reparacion, estatus_id
        ) VALUES (
          p_poliza_id, p_ajustador_id, v_num_reporte,
          p_fecha_hora_siniestro, NULL, NULL,
          p_conductor_momento, p_descripcion_hechos,
          p_dictamen_estatus_id, p_presupuesto, p_dictamen_estatus_id
        );

        SELECT LAST_INSERT_ID() AS id, v_num_reporte AS numero_reporte;
      END
    `);

    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_evidencia_siniestro(
        IN p_siniestro_id      INT,
        IN p_ajustador_id      INT,
        IN p_archivo           LONGBLOB,
        IN p_nombre_archivo    VARCHAR(255),
        IN p_tipo_mime         VARCHAR(100),
        IN p_tipo_evidencia    VARCHAR(50)
      )
      BEGIN
        INSERT INTO evidencias_siniestro (
          siniestro_id, ajustador_id, archivo_multimedia,
          nombre_archivo, tipo_mime, tipo_evidencia, fecha_subida
        ) VALUES (
          p_siniestro_id, p_ajustador_id, p_archivo,
          p_nombre_archivo, p_tipo_mime, p_tipo_evidencia, NOW()
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);

    await queryInterface.sequelize.query(`
      CREATE PROCEDURE sp_registrar_tercero_involucrado(
        IN p_siniestro_id       INT,
        IN p_marca_tercero      VARCHAR(80),
        IN p_modelo_tercero     VARCHAR(80),
        IN p_placas_tercero     VARCHAR(20),
        IN p_aseguradora_tercero VARCHAR(100),
        IN p_descripcion_danos  TEXT
      )
      BEGIN
        INSERT INTO terceros_involucrados (
          siniestro_id, marca_tercero, modelo_tercero,
          placas_tercero, aseguradora_tercero, descripcion_danos
        ) VALUES (
          p_siniestro_id, p_marca_tercero, p_modelo_tercero,
          p_placas_tercero, p_aseguradora_tercero, p_descripcion_danos
        );

        SELECT LAST_INSERT_ID() AS id;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_siniestro`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_evidencia_siniestro`);
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_registrar_tercero_involucrado`);
  }
};
