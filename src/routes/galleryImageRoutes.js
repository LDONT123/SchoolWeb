// src/routes/galleryImageRoutes.js
const express = require('express');
const router = express.Router();
const galleryImageController = require('../controllers/galleryImageController');
const validateRequest = require('../middlewares/validateRequest');
const { createGalleryImageSchema, updateGalleryImageSchema } = require('../validators/galleryImageValidation');

router.post('/', validateRequest(createGalleryImageSchema), galleryImageController.createGalleryImage);
router.get('/', galleryImageController.getAllGalleryImages);
router.get('/:id', galleryImageController.getGalleryImageById);
router.put('/:id', validateRequest(updateGalleryImageSchema), galleryImageController.updateGalleryImage);
router.delete('/:id', galleryImageController.deleteGalleryImage);

module.exports = router;
