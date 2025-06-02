// src/routes/facultyRoutes.js
const express = require('express');
const router = express.Router();
const facultyController = require('../controllers/facultyController');
const validateRequest = require('../middlewares/validateRequest');
const { createFacultySchema, updateFacultySchema } = require('../validators/facultyValidation');

router.post('/', validateRequest(createFacultySchema), facultyController.createFaculty);
router.get('/', facultyController.getAllFaculty);
router.get('/:id', facultyController.getFacultyById);
router.put('/:id', validateRequest(updateFacultySchema), facultyController.updateFaculty);
router.delete('/:id', facultyController.deleteFaculty);

module.exports = router;
