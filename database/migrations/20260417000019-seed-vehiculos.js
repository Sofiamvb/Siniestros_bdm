'use strict';

const vehiculos = [
  // NISSAN
  { marca: 'Nissan',        modelo: 'Versa',       anio: 2023, version: 'Advance CVT',       tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Nissan',        modelo: 'Sentra',      anio: 2021, version: 'SR',                tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Nissan',        modelo: 'X-Trail',     anio: 2022, version: 'Exclusive 3 Row',   tipo_vehiculo: 'SUV',       numero_pasajeros: 7, cilindros: 4, status: true },
  { marca: 'Nissan',        modelo: 'March',       anio: 2020, version: 'Advance',           tipo_vehiculo: 'Hatchback', numero_pasajeros: 5, cilindros: 4, status: true },
  // HONDA
  { marca: 'Honda',         modelo: 'Civic',       anio: 2022, version: 'Touring',           tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Honda',         modelo: 'CR-V',        anio: 2024, version: 'EX-L',              tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Honda',         modelo: 'Accord',      anio: 2020, version: 'Sport',             tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Honda',         modelo: 'HR-V',        anio: 2023, version: 'Uniq',              tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  // BMW
  { marca: 'BMW',           modelo: 'Serie 3',     anio: 2023, version: '330i M Sport',      tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'BMW',           modelo: 'X5',          anio: 2022, version: 'xDrive40i',         tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 6, status: true },
  { marca: 'BMW',           modelo: 'Serie 1',     anio: 2021, version: '118i Sport Line',   tipo_vehiculo: 'Hatchback', numero_pasajeros: 5, cilindros: 3, status: true },
  { marca: 'BMW',           modelo: 'X3',          anio: 2024, version: 'xDrive30i',         tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  // MERCEDES-BENZ
  { marca: 'Mercedes-Benz', modelo: 'Clase C',     anio: 2023, version: 'C 200 Sport',       tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Mercedes-Benz', modelo: 'GLE',         anio: 2024, version: '450 4MATIC',        tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 6, status: true },
  { marca: 'Mercedes-Benz', modelo: 'Clase A',     anio: 2020, version: 'A 200 Progressive', tipo_vehiculo: 'Hatchback', numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Mercedes-Benz', modelo: 'GLC',         anio: 2022, version: '300 4MATIC',        tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  // TOYOTA
  { marca: 'Toyota',        modelo: 'Corolla',     anio: 2023, version: 'LE',                tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Toyota',        modelo: 'RAV4',        anio: 2021, version: 'XLE',               tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Toyota',        modelo: 'Camry',       anio: 2022, version: 'SE',                tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Toyota',        modelo: 'Yaris',       anio: 2020, version: 'Core',              tipo_vehiculo: 'Hatchback', numero_pasajeros: 5, cilindros: 4, status: true },
  // VOLKSWAGEN
  { marca: 'Volkswagen',    modelo: 'Jetta',       anio: 2023, version: 'Comfortline',       tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Volkswagen',    modelo: 'Tiguan',      anio: 2021, version: 'R-Line',            tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Volkswagen',    modelo: 'Taos',        anio: 2024, version: 'Highline',          tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Volkswagen',    modelo: 'Golf',        anio: 2020, version: 'GTI',               tipo_vehiculo: 'Hatchback', numero_pasajeros: 5, cilindros: 4, status: true },
  // FORD
  { marca: 'Ford',          modelo: 'Mustang',     anio: 2022, version: 'GT V8',             tipo_vehiculo: 'Coupé',     numero_pasajeros: 4, cilindros: 8, status: true },
  { marca: 'Ford',          modelo: 'Explorer',    anio: 2023, version: 'XLT',               tipo_vehiculo: 'SUV',       numero_pasajeros: 7, cilindros: 4, status: true },
  { marca: 'Ford',          modelo: 'Escape',      anio: 2020, version: 'Titanium',          tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 4, status: true },
  { marca: 'Ford',          modelo: 'Bronco Sport',anio: 2024, version: 'Outer Banks',       tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 3, status: true },
  // CHEVROLET
  { marca: 'Chevrolet',     modelo: 'Onix',        anio: 2023, version: 'Premier',           tipo_vehiculo: 'Sedán',     numero_pasajeros: 5, cilindros: 3, status: true },
  { marca: 'Chevrolet',     modelo: 'Tracker',     anio: 2022, version: 'LS',                tipo_vehiculo: 'SUV',       numero_pasajeros: 5, cilindros: 3, status: true },
  { marca: 'Chevrolet',     modelo: 'Tahoe',       anio: 2024, version: 'RST',               tipo_vehiculo: 'SUV',       numero_pasajeros: 7, cilindros: 8, status: true },
  { marca: 'Chevrolet',     modelo: 'Camaro',      anio: 2021, version: 'SS',                tipo_vehiculo: 'Coupé',     numero_pasajeros: 4, cilindros: 8, status: true },
];

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface) {
    await queryInterface.bulkInsert('vehiculos', vehiculos);
  },

  async down(queryInterface) {
    await queryInterface.bulkDelete('vehiculos', null, { truncate: true });
  }
};
