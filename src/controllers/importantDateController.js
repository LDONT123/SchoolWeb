// src/controllers/importantDateController.js
const ImportantDate = require('../models/ImportantDate');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/important-dates
// @desc    Create an important date
// @access  Public
exports.createImportantDate = async (req, res) => {
    const { date, description, category } = req.body;
    try {
        // Basic check for duplicates (e.g., same date and description)
        let importantDate = await ImportantDate.findOne({ date, description });
        if (importantDate) {
            return res.status(400).json({ errors: [{ msg: 'Important date with this date and description already exists' }] });
        }

        importantDate = new ImportantDate({
            id: uuidv4(),
            date,
            description,
            category
        });

        await importantDate.save();
        res.status(201).json(importantDate);
    } catch (err) {
        console.error(err.message);
        // Add more specific error handling for enum validation if needed
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   GET api/important-dates
// @desc    Get all important dates
// @access  Public
exports.getAllImportantDates = async (req, res) => {
    try {
        const importantDates = await ImportantDate.find().sort({ date: 1 }); // Sort by date ascending
        res.json(importantDates);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/important-dates/:id
// @desc    Get important date by ID
// @access  Public
exports.getImportantDateById = async (req, res) => {
    try {
        const importantDate = await ImportantDate.findOne({ id: req.params.id });
        if (!importantDate) {
            return res.status(404).json({ msg: 'Important date not found' });
        }
        res.json(importantDate);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/important-dates/:id
// @desc    Update an important date
// @access  Public
exports.updateImportantDate = async (req, res) => {
    const { date, description, category } = req.body;
    const dateFields = {};
    if (date) dateFields.date = date;
    if (description) dateFields.description = description;
    if (category) dateFields.category = category;

    try {
        let importantDate = await ImportantDate.findOne({ id: req.params.id });
        if (!importantDate) {
            return res.status(404).json({ msg: 'Important date not found' });
        }

        importantDate = await ImportantDate.findOneAndUpdate(
            { id: req.params.id },
            { $set: dateFields },
            { new: true, runValidators: true } // runValidators to ensure enum is correct on update
        );

        res.json(importantDate);
    } catch (err) {
        console.error(err.message);
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/important-dates/:id
// @desc    Delete an important date
// @access  Public
exports.deleteImportantDate = async (req, res) => {
    try {
        const importantDate = await ImportantDate.findOne({ id: req.params.id });
        if (!importantDate) {
            return res.status(404).json({ msg: 'Important date not found' });
        }

        await ImportantDate.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Important date removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
