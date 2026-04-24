'use strict';

/**
 * SP unificado sp_usuario que envuelve:
 *  - sp_registrar_usuario  → p_accion = 'registro'
 *  - sp_login_usuario      → p_accion = 'login'
 *  - sp_actualizar_perfil  → p_accion = 'modificar'
 *
 * Los 3 SPs originales se mantienen intactos.
 */
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_usuario`);
    await q(`
      CREATE PROCEDURE sp_usuario(
        IN p_accion           VARCHAR(20),
        IN p_id               INT,
        IN p_nombre           VARCHAR(255),
        IN p_apellidos        VARCHAR(255),
        IN p_fecha_nacimiento DATE,
        IN p_genero           VARCHAR(255),
        IN p_email            VARCHAR(255),
        IN p_password         VARCHAR(255),
        IN p_alias            VARCHAR(255),
        IN p_rol_id           INT,
        IN p_foto             LONGBLOB
      )
      BEGIN
        CASE p_accion

          WHEN 'registro' THEN
            INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, genero, email, password, alias, rol_id, foto)
            VALUES (p_nombre, p_apellidos, p_fecha_nacimiento, p_genero, p_email, p_password, p_alias, p_rol_id, p_foto);
            SELECT LAST_INSERT_ID() AS id;

          WHEN 'login' THEN
            SELECT
              u.id,
              u.nombre,
              u.apellidos,
              u.fecha_nacimiento,
              u.foto,
              u.genero,
              u.email,
              u.password,
              u.alias,
              u.rol_id
            FROM usuarios u
            WHERE u.email = p_email
            LIMIT 1;

          WHEN 'modificar' THEN
            UPDATE usuarios
            SET nombre           = p_nombre,
                apellidos        = p_apellidos,
                fecha_nacimiento = p_fecha_nacimiento,
                genero           = p_genero,
                alias            = p_alias,
                foto             = IF(p_foto IS NULL OR p_foto = '', foto, p_foto)
            WHERE id = p_id;
            SELECT ROW_COUNT() AS actualizado;

          ELSE
            SIGNAL SQLSTATE '45000'
              SET MESSAGE_TEXT = 'Acción no válida. Use: registro, login o modificar';

        END CASE;
      END
    `);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query(`DROP PROCEDURE IF EXISTS sp_usuario`);
  }
};
