'use strict';

/**
 * Corrige dos problemas en la vista `diccionario`:
 *
 * 1. usuarios.foto perdió su COMMENT al cambiar de VARCHAR(255) a LONGBLOB
 *    en una migración posterior sin incluir COMMENT.
 *
 * 2. La vista `diccionario` se mostraba a sí misma en los resultados porque
 *    el WHERE incluía todas las vistas del schema (incluyendo a sí misma).
 *    Se agrega `AND t.table_name <> 'diccionario'` para excluirla.
 */
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // 1. Restaurar COMMENT de usuarios.foto (columna ahora es LONGBLOB)
    await q(`
      ALTER TABLE usuarios
        MODIFY COLUMN foto LONGBLOB NULL
          COMMENT 'Foto de perfil del usuario almacenada como binario LONGBLOB; se sirve como base64 en la sesión'
    `);

    // 2. Recrear vista excluyéndose a sí misma
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
        AND t.table_name   <> 'diccionario'
      ORDER BY t.table_name, c.ordinal_position
    `);
  },

  async down(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Revertir foto a sin COMMENT
    await q(`
      ALTER TABLE usuarios
        MODIFY COLUMN foto LONGBLOB NULL
          COMMENT 'Ruta relativa a la foto de perfil del usuario'
    `);

    // Restaurar vista sin la exclusión
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
  }
};
