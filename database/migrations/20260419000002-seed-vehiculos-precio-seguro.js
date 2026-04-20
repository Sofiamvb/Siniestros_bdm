'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    const q = (sql) => queryInterface.sequelize.query(sql);

    // Cada UPDATE identifica el vehículo por la combinación única marca+modelo+anio+version
    await q(`UPDATE vehiculos SET precio_seguro =  12500.00 WHERE marca = 'Nissan'        AND modelo = 'Versa'        AND anio = 2023 AND version = 'Advance CVT'`);
    await q(`UPDATE vehiculos SET precio_seguro =  14800.00 WHERE marca = 'Nissan'        AND modelo = 'Sentra'       AND anio = 2021 AND version = 'SR'`);
    await q(`UPDATE vehiculos SET precio_seguro =  18500.00 WHERE marca = 'Nissan'        AND modelo = 'X-Trail'      AND anio = 2022 AND version = 'Exclusive 3 Row'`);
    await q(`UPDATE vehiculos SET precio_seguro =   9500.00 WHERE marca = 'Nissan'        AND modelo = 'March'        AND anio = 2020 AND version = 'Advance'`);

    await q(`UPDATE vehiculos SET precio_seguro =  16500.00 WHERE marca = 'Honda'         AND modelo = 'Civic'        AND anio = 2022 AND version = 'Touring'`);
    await q(`UPDATE vehiculos SET precio_seguro =  22000.00 WHERE marca = 'Honda'         AND modelo = 'CR-V'         AND anio = 2024 AND version = 'EX-L'`);
    await q(`UPDATE vehiculos SET precio_seguro =  19000.00 WHERE marca = 'Honda'         AND modelo = 'Accord'       AND anio = 2020 AND version = 'Sport'`);
    await q(`UPDATE vehiculos SET precio_seguro =  17800.00 WHERE marca = 'Honda'         AND modelo = 'HR-V'         AND anio = 2023 AND version = 'Uniq'`);

    await q(`UPDATE vehiculos SET precio_seguro =  35000.00 WHERE marca = 'BMW'           AND modelo = 'Serie 3'      AND anio = 2023 AND version = '330i M Sport'`);
    await q(`UPDATE vehiculos SET precio_seguro =  45000.00 WHERE marca = 'BMW'           AND modelo = 'X5'           AND anio = 2022 AND version = 'xDrive40i'`);
    await q(`UPDATE vehiculos SET precio_seguro =  28500.00 WHERE marca = 'BMW'           AND modelo = 'Serie 1'      AND anio = 2021 AND version = '118i Sport Line'`);
    await q(`UPDATE vehiculos SET precio_seguro =  38000.00 WHERE marca = 'BMW'           AND modelo = 'X3'           AND anio = 2024 AND version = 'xDrive30i'`);

    await q(`UPDATE vehiculos SET precio_seguro =  36500.00 WHERE marca = 'Mercedes-Benz' AND modelo = 'Clase C'      AND anio = 2023 AND version = 'C 200 Sport'`);
    await q(`UPDATE vehiculos SET precio_seguro =  48000.00 WHERE marca = 'Mercedes-Benz' AND modelo = 'GLE'          AND anio = 2024 AND version = '450 4MATIC'`);
    await q(`UPDATE vehiculos SET precio_seguro =  26000.00 WHERE marca = 'Mercedes-Benz' AND modelo = 'Clase A'      AND anio = 2020 AND version = 'A 200 Progressive'`);
    await q(`UPDATE vehiculos SET precio_seguro =  39500.00 WHERE marca = 'Mercedes-Benz' AND modelo = 'GLC'          AND anio = 2022 AND version = '300 4MATIC'`);

    await q(`UPDATE vehiculos SET precio_seguro =  13500.00 WHERE marca = 'Toyota'        AND modelo = 'Corolla'      AND anio = 2023 AND version = 'LE'`);
    await q(`UPDATE vehiculos SET precio_seguro =  18000.00 WHERE marca = 'Toyota'        AND modelo = 'RAV4'         AND anio = 2021 AND version = 'XLE'`);
    await q(`UPDATE vehiculos SET precio_seguro =  17500.00 WHERE marca = 'Toyota'        AND modelo = 'Camry'        AND anio = 2022 AND version = 'SE'`);
    await q(`UPDATE vehiculos SET precio_seguro =  10500.00 WHERE marca = 'Toyota'        AND modelo = 'Yaris'        AND anio = 2020 AND version = 'Core'`);

    await q(`UPDATE vehiculos SET precio_seguro =  14500.00 WHERE marca = 'Volkswagen'    AND modelo = 'Jetta'        AND anio = 2023 AND version = 'Comfortline'`);
    await q(`UPDATE vehiculos SET precio_seguro =  19500.00 WHERE marca = 'Volkswagen'    AND modelo = 'Tiguan'       AND anio = 2021 AND version = 'R-Line'`);
    await q(`UPDATE vehiculos SET precio_seguro =  18800.00 WHERE marca = 'Volkswagen'    AND modelo = 'Taos'         AND anio = 2024 AND version = 'Highline'`);
    await q(`UPDATE vehiculos SET precio_seguro =  21000.00 WHERE marca = 'Volkswagen'    AND modelo = 'Golf'         AND anio = 2020 AND version = 'GTI'`);

    await q(`UPDATE vehiculos SET precio_seguro =  31000.00 WHERE marca = 'Ford'          AND modelo = 'Mustang'      AND anio = 2022 AND version = 'GT V8'`);
    await q(`UPDATE vehiculos SET precio_seguro =  24500.00 WHERE marca = 'Ford'          AND modelo = 'Explorer'     AND anio = 2023 AND version = 'XLT'`);
    await q(`UPDATE vehiculos SET precio_seguro =  16800.00 WHERE marca = 'Ford'          AND modelo = 'Escape'       AND anio = 2020 AND version = 'Titanium'`);
    await q(`UPDATE vehiculos SET precio_seguro =  23000.00 WHERE marca = 'Ford'          AND modelo = 'Bronco Sport' AND anio = 2024 AND version = 'Outer Banks'`);

    await q(`UPDATE vehiculos SET precio_seguro =  11800.00 WHERE marca = 'Chevrolet'     AND modelo = 'Onix'         AND anio = 2023 AND version = 'Premier'`);
    await q(`UPDATE vehiculos SET precio_seguro =  13800.00 WHERE marca = 'Chevrolet'     AND modelo = 'Tracker'      AND anio = 2022 AND version = 'LS'`);
    await q(`UPDATE vehiculos SET precio_seguro =  33500.00 WHERE marca = 'Chevrolet'     AND modelo = 'Tahoe'        AND anio = 2024 AND version = 'RST'`);
    await q(`UPDATE vehiculos SET precio_seguro =  29500.00 WHERE marca = 'Chevrolet'     AND modelo = 'Camaro'       AND anio = 2021 AND version = 'SS'`);
  },

  async down(queryInterface) {
    await queryInterface.sequelize.query('UPDATE vehiculos SET precio_seguro = 0.00');
  }
};
