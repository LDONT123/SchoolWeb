// src/controllers/campusResourceController.js
const CampusResource = require('../models/CampusResource');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/campus-resources
// @desc    Create a campus resource
// @access  Public // Consider admin-only
exports.createCampusResource = async (req, res) => {
    const { name, description, link, icon, category } = req.body;
    try {
        // Basic check for duplicates (e.g., same name and category)
        let resource = await CampusResource.findOne({ name, category });
        if (resource) {
            return res.status(400).json({ errors: [{ msg: 'Campus resource with this name and category already exists' }] });
        }

        resource = new CampusResource({
            id: uuidv4(),
            name,
            description,
            link,
            icon,
            category
        });

        await resource.save();
        res.status(201).json(resource);
    } catch (err) {
        console.error(err.message);
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   GET api/campus-resources
// @desc    Get all campus resources
// @access  Public
exports.getAllCampusResources = async (req, res) => {
    try {
        // Example: Allow filtering by category
        const page = parseInt(req.query.page, 10) || 1;
        const limit = parseInt(req.query.limit, 10) || 10;
        const sortBy = req.query.sortBy || 'name';
        const order = req.query.order === 'desc' ? -1 : 1;
        
        const { category } = req.query; // Keep existing filter
        const filter = {};
        if (category) {
            filter.category = category;
        }

        const skip = (page - 1) * limit;
        const sortOptions = {};
        if (sortBy) sortOptions[sortBy] = order;

        const resources = await CampusResource.find(filter) // Apply filter
            .sort(sortOptions)
            .skip(skip)
            .limit(limit);
        
        const totalResources = await CampusResource.countDocuments(filter);
        const totalPages = Math.ceil(totalResources / limit);

        res.json({
            data: resources,
            pagination: {
                currentPage: page,
                totalPages,
                totalItems: totalResources,
                itemsPerPage: limit,
                ...(category && { filterCategory: category }) // Include filter info in response
            }
        });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/campus-resources/:id
// @desc    Get campus resource by ID
// @access  Public
exports.getCampusResourceById = async (req, res) => {
    try {
        const resource = await CampusResource.findOne({ id: req.params.id });
        if (!resource) {
            return res.status(404).json({ msg: 'Campus resource not found' });
        }
        res.json(resource);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/campus-resources/:id
// @desc    Update a campus resource
// @access  Public // Consider admin-only
exports.updateCampusResource = async (req, res) => {
    const { name, description, link, icon, category } = req.body;
    const resourceFields = {};
    if (name) resourceFields.name = name;
    if (description) resourceFields.description = description;
    if (link !== undefined) resourceFields.link = link;
    if (icon !== undefined) resourceFields.icon = icon;
    if (category) resourceFields.category = category;

    try {
        let resource = await CampusResource.findOne({ id: req.params.id });
        if (!resource) {
            return res.status(404).json({ msg: 'Campus resource not found' });
        }

        resource = await CampusResource.findOneAndUpdate(
            { id: req.params.id },
            { $set: resourceFields },
            { new: true, runValidators: true } // runValidators for enum category
        );

        res.json(resource);
    } catch (err) {
        console.error(err.message);
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/campus-resources/:id
// @desc    Delete a campus resource
// @access  Public // Consider admin-only
exports.deleteCampusResource = async (req, res) => {
    try {
        const resource = await CampusResource.findOne({ id: req.params.id });
        if (!resource) {
            return res.status(404).json({ msg: 'Campus resource not found' });
        }

        await CampusResource.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Campus resource removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
