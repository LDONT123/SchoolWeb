// src/routes/campusResourceRoutes.js
const express = require('express');
const router = express.Router();
const campusResourceController = require('../controllers/campusResourceController');

router.post('/', campusResourceController.createCampusResource);
router.get('/', campusResourceController.getAllCampusResources);
router.get('/:id', campusResourceController.getCampusResourceById);
router.put('/:id', campusResourceController.updateCampusResource);
router.delete('/:id', campusResourceController.deleteCampusResource);

module.exports = router;
