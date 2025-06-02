// src/routes/importantDateRoutes.js
const express = require('express');
const router = express.Router();
const importantDateController = require('../controllers/importantDateController');

router.post('/', importantDateController.createImportantDate);
router.get('/', importantDateController.getAllImportantDates);
router.get('/:id', importantDateController.getImportantDateById);
router.put('/:id', importantDateController.updateImportantDate);
router.delete('/:id', importantDateController.deleteImportantDate);

module.exports = router;
