'use strict';

/**
 * Agrega usuario_id al resultado de sp_enviar_mensaje y sp_get_mensajes_chat
 * para que el frontend pueda distinguir los mensajes propios de los ajenos.
 */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await q(`
      CREATE PROCEDURE sp_enviar_mensaje(
        IN p_chat_id    INT,
        IN p_usuario_id INT,
        IN p_mensaje    TEXT
      )
      BEGIN
        INSERT INTO chat_mensajes (chat_id, usuario_id, mensaje)
        VALUES (p_chat_id, p_usuario_id, p_mensaje);

        SELECT
          cm.id,
          cm.usuario_id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.id = LAST_INSERT_ID();
      END
    `);

    await q(`DROP PROCEDURE IF EXISTS sp_get_mensajes_chat`);
    await q(`
      CREATE PROCEDURE sp_get_mensajes_chat(
        IN p_chat_id   INT,
        IN p_desde_id  INT
      )
      BEGIN
        SELECT
          cm.id,
          cm.usuario_id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.chat_id = p_chat_id
          AND cm.id      > p_desde_id
        ORDER BY cm.id ASC
        LIMIT 50;
      END
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await q(`
      CREATE PROCEDURE sp_enviar_mensaje(
        IN p_chat_id    INT,
        IN p_usuario_id INT,
        IN p_mensaje    TEXT
      )
      BEGIN
        INSERT INTO chat_mensajes (chat_id, usuario_id, mensaje)
        VALUES (p_chat_id, p_usuario_id, p_mensaje);

        SELECT
          cm.id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.id = LAST_INSERT_ID();
      END
    `);

    await q(`DROP PROCEDURE IF EXISTS sp_get_mensajes_chat`);
    await q(`
      CREATE PROCEDURE sp_get_mensajes_chat(
        IN p_chat_id   INT,
        IN p_desde_id  INT
      )
      BEGIN
        SELECT
          cm.id,
          cm.mensaje,
          cm.created_at,
          u.nombre,
          u.apellidos,
          u.rol_id
        FROM chat_mensajes cm
        INNER JOIN usuarios u ON u.id = cm.usuario_id
        WHERE cm.chat_id = p_chat_id
          AND cm.id      > p_desde_id
        ORDER BY cm.id ASC
        LIMIT 50;
      END
    `);
  }
};
