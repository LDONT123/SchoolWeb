// src/routes/documentRoutes.js
const express = require('express');
const router = express.Router();
const documentController = require('../controllers/documentController');
const validateRequest = require('../middlewares/validateRequest');
const { createDocumentSchema, updateDocumentSchema } = require('../validators/documentValidation');

router.post('/', validateRequest(createDocumentSchema), documentController.createDocument);
router.get('/', documentController.getAllDocuments);
router.get('/:id', documentController.getDocumentById);
router.put('/:id', validateRequest(updateDocumentSchema), documentController.updateDocument);
router.delete('/:id', documentController.deleteDocument);

module.exports = router;
