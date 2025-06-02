// src/routes/eventRoutes.js
const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');

// POST /api/events - Create a new event
router.post('/', eventController.createEvent);

// GET /api/events - Get all events
router.get('/', eventController.getAllEvents);

// GET /api/events/:id - Get a specific event by ID
router.get('/:id', eventController.getEventById);

// PUT /api/events/:id - Update a specific event by ID
router.put('/:id', eventController.updateEvent);

// DELETE /api/events/:id - Delete a specific event by ID
router.delete('/:id', eventController.deleteEvent);

module.exports = router;
