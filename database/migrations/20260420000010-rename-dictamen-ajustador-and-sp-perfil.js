'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Renombrar columna
    await q(`
      ALTER TABLE siniestros
        CHANGE COLUMN dictamen_ajustador dictamen_supervisor VARCHAR(255) NULL
    `);

    // SP para obtener datos del usuario por id (para pre-poblar el formulario)
    await q(`DROP PROCEDURE IF EXISTS sp_get_usuario_by_id`);
    await q(`
      CREATE PROCEDURE sp_get_usuario_by_id(IN p_id INT)
      BEGIN
        SELECT id, nombre, apellidos, fecha_nacimiento, genero, email, alias, foto
        FROM usuarios
        WHERE id = p_id
        LIMIT 1;
      END
    `);

    // SP para actualizar perfil (sin cambiar password ni rol)
    await q(`DROP PROCEDURE IF EXISTS sp_actualizar_perfil`);
    await q(`
      CREATE PROCEDURE sp_actualizar_perfil(
        IN p_id               INT,
        IN p_nombre           VARCHAR(100),
        IN p_apellidos        VARCHAR(150),
        IN p_fecha_nacimiento DATE,
        IN p_genero           VARCHAR(20),
        IN p_alias            VARCHAR(80),
        IN p_foto             LONGBLOB
      )
      BEGIN
        UPDATE usuarios
        SET nombre           = p_nombre,
            apellidos        = p_apellidos,
            fecha_nacimiento = p_fecha_nacimiento,
            genero           = p_genero,
            alias            = p_alias,
            foto             = IF(p_foto IS NULL OR p_foto = '', foto, p_foto)
        WHERE id = p_id;

        SELECT ROW_COUNT() AS actualizado;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`ALTER TABLE siniestros CHANGE COLUMN dictamen_supervisor dictamen_ajustador VARCHAR(255) NULL`);
    await q(`DROP PROCEDURE IF EXISTS sp_get_usuario_by_id`);
    await q(`DROP PROCEDURE IF EXISTS sp_actualizar_perfil`);
  }
};
