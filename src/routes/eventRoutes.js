// src/routes/eventRoutes.js
const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');
const validateRequest = require('../middlewares/validateRequest');
const { createEventSchema, updateEventSchema } = require('../validators/eventValidation');

router.post('/', validateRequest(createEventSchema), eventController.createEvent);
router.get('/', eventController.getAllEvents);
router.get('/:id', eventController.getEventById);
router.put('/:id', validateRequest(updateEventSchema), eventController.updateEvent);
router.delete('/:id', eventController.deleteEvent);

module.exports = router;
