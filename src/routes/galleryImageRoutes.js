// src/routes/galleryImageRoutes.js
const express = require('express');
const router = express.Router();
const galleryImageController = require('../controllers/galleryImageController');

router.post('/', galleryImageController.createGalleryImage);
router.get('/', galleryImageController.getAllGalleryImages);
router.get('/:id', galleryImageController.getGalleryImageById);
router.put('/:id', galleryImageController.updateGalleryImage);
router.delete('/:id', galleryImageController.deleteGalleryImage);

module.exports = router;
