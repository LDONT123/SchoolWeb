// src/routes/importantDateRoutes.js
const express = require('express');
const router = express.Router();
const importantDateController = require('../controllers/importantDateController');
const validateRequest = require('../middlewares/validateRequest');
const { createImportantDateSchema, updateImportantDateSchema } = require('../validators/importantDateValidation');

router.post('/', validateRequest(createImportantDateSchema), importantDateController.createImportantDate);
router.get('/', importantDateController.getAllImportantDates);
router.get('/:id', importantDateController.getImportantDateById);
router.put('/:id', validateRequest(updateImportantDateSchema), importantDateController.updateImportantDate);
router.delete('/:id', importantDateController.deleteImportantDate);

module.exports = router;
