// src/controllers/eventController.js
const Event = require('../models/Event');
const { v4: uuidv4 } = require('uuid'); // To generate unique IDs
const mongoose = require('mongoose'); // Added to check for valid ObjectId

// @route   POST api/events
// @desc    Create an event
// @access  Public // Decide on access control later (e.g., private, admin-only)
exports.createEvent = async (req, res) => {
    const { title, date, time, location, description, imageUrl, imageHint } = req.body;
    try {
        let event = await Event.findOne({ title, date, location }); // Basic check for duplicates
        if (event) {
            return res.status(400).json({ errors: [{ msg: 'Event already exists with this title, date, and location' }] });
        }

        event = new Event({
            id: uuidv4(), // Generate a unique ID
            title,
            date,
            time,
            location,
            description,
            imageUrl,
            imageHint
        });

        await event.save();
        res.status(201).json(event);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/events
// @desc    Get all events
// @access  Public
exports.getAllEvents = async (req, res) => {
    try {
        const events = await Event.find().sort({ date: -1 }); // Default sort by date descending
        res.json(events);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/events/:id
// @desc    Get event by ID
// @access  Public
exports.getEventById = async (req, res) => {
    try {
        const event = await Event.findOne({ id: req.params.id });
        if (!event) {
            return res.status(404).json({ msg: 'Event not found' });
        }
        res.json(event);
    } catch (err) {
        console.error(err.message);
        // The original code had a check for err.kind === 'ObjectId'
        // This check is relevant if you are querying by MongoDB's default _id.
        // Since we are using a custom `id` field (uuid), this specific check might not be directly applicable
        // unless `req.params.id` is somehow still expected to be an ObjectId by Mongoose in some error paths.
        // For querying by a custom string 'id', a general error or a not found is more typical.
        // However, if mongoose somehow throws an ObjectId related error for other reasons, it might be useful.
        // For now, let's keep it, but be mindful that our primary lookup is `id: req.params.id` (a string UUID).
        if (err.kind === 'ObjectId' && !mongoose.Types.ObjectId.isValid(req.params.id)) { 
             return res.status(404).json({ msg: 'Event not found, invalid ID format' }); // Clarified message
        }
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/events/:id
// @desc    Update an event
// @access  Public // Decide on access control later
exports.updateEvent = async (req, res) => {
    const { title, date, time, location, description, imageUrl, imageHint } = req.body;
    const eventFields = {};
    if (title) eventFields.title = title;
    if (date) eventFields.date = date;
    if (time !== undefined) eventFields.time = time; // Allow clearing time
    if (location) eventFields.location = location;
    if (description) eventFields.description = description;
    if (imageUrl !== undefined) eventFields.imageUrl = imageUrl; // Allow clearing imageUrl
    if (imageHint !== undefined) eventFields.imageHint = imageHint; // Allow clearing imageHint


    try {
        let event = await Event.findOne({ id: req.params.id });
        if (!event) {
            return res.status(404).json({ msg: 'Event not found' });
        }

        event = await Event.findOneAndUpdate(
            { id: req.params.id },
            { $set: eventFields },
            { new: true }
        );

        res.json(event);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/events/:id
// @desc    Delete an event
// @access  Public // Decide on access control later
exports.deleteEvent = async (req, res) => {
    try {
        const event = await Event.findOne({ id: req.params.id });
        if (!event) {
            return res.status(404).json({ msg: 'Event not found' });
        }

        await Event.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Event removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
