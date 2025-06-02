// src/controllers/facultyController.js
const Faculty = require('../models/Faculty');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/faculty
// @desc    Create a faculty member
// @access  Public // Consider making this admin-only later
exports.createFaculty = async (req, res) => {
    const { name, title, department, email, phone, office, bio, imageUrl, imageHint } = req.body;
    try {
        // Check if faculty member with the same email already exists
        let facultyMember = await Faculty.findOne({ email });
        if (facultyMember) {
            return res.status(400).json({ errors: [{ msg: 'Faculty member with this email already exists' }] });
        }

        facultyMember = new Faculty({
            id: uuidv4(),
            name,
            title,
            department,
            email,
            phone,
            office,
            bio,
            imageUrl,
            imageHint
        });

        await facultyMember.save();
        res.status(201).json(facultyMember);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/faculty
// @desc    Get all faculty members
// @access  Public
exports.getAllFaculty = async (req, res) => {
    try {
        const page = parseInt(req.query.page, 10) || 1;
        const limit = parseInt(req.query.limit, 10) || 10;
        const sortBy = req.query.sortBy || 'name'; // Default sort by name
        const order = req.query.order === 'desc' ? -1 : 1; // Default order ascending

        const skip = (page - 1) * limit;
        const sortOptions = {};
        if (sortBy) sortOptions[sortBy] = order;

        const facultyMembers = await Faculty.find()
            .sort(sortOptions)
            .skip(skip)
            .limit(limit);
        
        const totalFaculty = await Faculty.countDocuments();
        const totalPages = Math.ceil(totalFaculty / limit);

        res.json({
            data: facultyMembers,
            pagination: {
                currentPage: page,
                totalPages,
                totalItems: totalFaculty,
                itemsPerPage: limit
            }
        });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/faculty/:id
// @desc    Get faculty member by ID
// @access  Public
exports.getFacultyById = async (req, res) => {
    try {
        const facultyMember = await Faculty.findOne({ id: req.params.id });
        if (!facultyMember) {
            return res.status(404).json({ msg: 'Faculty member not found' });
        }
        res.json(facultyMember);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/faculty/:id
// @desc    Update a faculty member
// @access  Public // Consider making this admin-only later
exports.updateFaculty = async (req, res) => {
    const { name, title, department, email, phone, office, bio, imageUrl, imageHint } = req.body;
    const facultyFields = {};
    if (name) facultyFields.name = name;
    if (title) facultyFields.title = title;
    if (department) facultyFields.department = department;
    if (email) facultyFields.email = email; // Add check for email uniqueness if changed
    if (phone !== undefined) facultyFields.phone = phone;
    if (office !== undefined) facultyFields.office = office;
    if (bio !== undefined) facultyFields.bio = bio;
    if (imageUrl !== undefined) facultyFields.imageUrl = imageUrl;
    if (imageHint !== undefined) facultyFields.imageHint = imageHint;

    try {
        let facultyMember = await Faculty.findOne({ id: req.params.id });
        if (!facultyMember) {
            return res.status(404).json({ msg: 'Faculty member not found' });
        }

        // If email is being changed, check if the new email already exists for another faculty member
        if (email && email !== facultyMember.email) {
            const existingFaculty = await Faculty.findOne({ email });
            if (existingFaculty) {
                return res.status(400).json({ errors: [{ msg: 'Another faculty member with this email already exists' }] });
            }
        }

        facultyMember = await Faculty.findOneAndUpdate(
            { id: req.params.id },
            { $set: facultyFields },
            { new: true }
        );

        res.json(facultyMember);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/faculty/:id
// @desc    Delete a faculty member
// @access  Public // Consider making this admin-only later
exports.deleteFaculty = async (req, res) => {
    try {
        const facultyMember = await Faculty.findOne({ id: req.params.id });
        if (!facultyMember) {
            return res.status(404).json({ msg: 'Faculty member not found' });
        }

        await Faculty.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Faculty member removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
