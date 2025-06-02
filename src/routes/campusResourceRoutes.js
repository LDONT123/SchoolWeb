// src/routes/campusResourceRoutes.js
const express = require('express');
const router = express.Router();
const campusResourceController = require('../controllers/campusResourceController');
const validateRequest = require('../middlewares/validateRequest');
const { createCampusResourceSchema, updateCampusResourceSchema } = require('../validators/campusResourceValidation');

router.post('/', validateRequest(createCampusResourceSchema), campusResourceController.createCampusResource);
router.get('/', campusResourceController.getAllCampusResources);
router.get('/:id', campusResourceController.getCampusResourceById);
router.put('/:id', validateRequest(updateCampusResourceSchema), campusResourceController.updateCampusResource);
router.delete('/:id', campusResourceController.deleteCampusResource);

module.exports = router;
