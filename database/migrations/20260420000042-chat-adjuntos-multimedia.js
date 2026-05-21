'use strict';

/**
 * Agrega soporte multimedia al chat:
 *  - imagen LONGBLOB  → imágenes guardadas en BD como BLOB
 *  - imagen_mime      → tipo MIME de la imagen
 *  - video_ruta       → ruta en filesystem (public/chat-videos/{chat_id}/)
 * Actualiza sp_enviar_mensaje y sp_get_mensajes_chat para incluir los nuevos campos.
 */
module.exports = {
  async up(queryInterface, Sequelize) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    await queryInterface.addColumn('chat_mensajes', 'imagen', {
      type: Sequelize.BLOB('long'), allowNull: true, defaultValue: null,
    });
    await queryInterface.addColumn('chat_mensajes', 'imagen_mime', {
      type: Sequelize.STRING(50), allowNull: true, defaultValue: null,
    });
    await queryInterface.addColumn('chat_mensajes', 'video_ruta', {
      type: Sequelize.STRING(500), allowNull: true, defaultValue: null,
    });

    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await q(`
      CREATE PROCEDURE sp_enviar_mensaje(
        IN p_chat_id     INT,
        IN p_usuario_id  INT,
        IN p_mensaje     TEXT,
        IN p_imagen      LONGBLOB,
        IN p_imagen_mime VARCHAR(50),
        IN p_video_ruta  VARCHAR(500)
      )
      BEGIN
        INSERT INTO chat_mensajes (chat_id, usuario_id, mensaje, imagen, imagen_mime, video_ruta)
        VALUES (p_chat_id, p_usuario_id, p_mensaje, p_imagen, p_imagen_mime, p_video_ruta);

        SELECT
          cm.id,
          cm.usuario_id,
          cm.mensaje,
          cm.imagen,
          cm.imagen_mime,
          cm.video_ruta,
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
        IN p_chat_id  INT,
        IN p_desde_id INT
      )
      BEGIN
        SELECT
          cm.id,
          cm.usuario_id,
          cm.mensaje,
          cm.imagen,
          cm.imagen_mime,
          cm.video_ruta,
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
    await q(`DROP PROCEDURE IF EXISTS sp_get_mensajes_chat`);
    await q(`DROP PROCEDURE IF EXISTS sp_enviar_mensaje`);
    await queryInterface.removeColumn('chat_mensajes', 'video_ruta');
    await queryInterface.removeColumn('chat_mensajes', 'imagen_mime');
    await queryInterface.removeColumn('chat_mensajes', 'imagen');
  }
};
