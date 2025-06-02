// src/controllers/documentController.js
const Document = require('../models/Document');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/documents
// @desc    Create a document
// @access  Public // Consider admin-only
exports.createDocument = async (req, res) => {
    const { name, url, type, description } = req.body;
    try {
        // Basic check for duplicates (e.g., same url)
        let document = await Document.findOne({ url });
        if (document) {
            return res.status(400).json({ errors: [{ msg: 'Document with this URL already exists' }] });
        }

        document = new Document({
            id: uuidv4(),
            name,
            url,
            type,
            description
        });

        await document.save();
        res.status(201).json(document);
    } catch (err) {
        console.error(err.message);
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   GET api/documents
// @desc    Get all documents
// @access  Public
exports.getAllDocuments = async (req, res) => {
    try {
        // Example: Allow filtering by type
        const page = parseInt(req.query.page, 10) || 1;
        const limit = parseInt(req.query.limit, 10) || 10;
        const sortBy = req.query.sortBy || 'name';
        const order = req.query.order === 'desc' ? -1 : 1;

        const { type } = req.query; // Keep existing filter
        const filter = {};
        if (type) {
            filter.type = type;
        }

        const skip = (page - 1) * limit;
        const sortOptions = {};
        if (sortBy) sortOptions[sortBy] = order;

        const documents = await Document.find(filter) // Apply filter
            .sort(sortOptions)
            .skip(skip)
            .limit(limit);
        
        const totalDocuments = await Document.countDocuments(filter);
        const totalPages = Math.ceil(totalDocuments / limit);

        res.json({
            data: documents,
            pagination: {
                currentPage: page,
                totalPages,
                totalItems: totalDocuments,
                itemsPerPage: limit,
                ...(type && { filterType: type }) // Include filter info in response
            }
        });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/documents/:id
// @desc    Get document by ID
// @access  Public
exports.getDocumentById = async (req, res) => {
    try {
        const document = await Document.findOne({ id: req.params.id });
        if (!document) {
            return res.status(404).json({ msg: 'Document not found' });
        }
        res.json(document);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/documents/:id
// @desc    Update a document
// @access  Public // Consider admin-only
exports.updateDocument = async (req, res) => {
    const { name, url, type, description } = req.body;
    const documentFields = {};
    if (name) documentFields.name = name;
    if (url) documentFields.url = url;
    if (type) documentFields.type = type;
    if (description !== undefined) documentFields.description = description;


    try {
        let document = await Document.findOne({ id: req.params.id });
        if (!document) {
            return res.status(404).json({ msg: 'Document not found' });
        }

        // If URL is being changed, check if the new URL already exists for another document
        if (url && url !== document.url) {
            const existingDocument = await Document.findOne({ url });
            if (existingDocument) {
                return res.status(400).json({ errors: [{ msg: 'Another document with this URL already exists' }] });
            }
        }

        document = await Document.findOneAndUpdate(
            { id: req.params.id },
            { $set: documentFields },
            { new: true, runValidators: true } // runValidators for enum type
        );

        res.json(document);
    } catch (err) {
        console.error(err.message);
        if (err.name === 'ValidationError') {
            return res.status(400).json({ errors: [{ msg: err.message }] });
        }
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/documents/:id
// @desc    Delete a document
// @access  Public // Consider admin-only
exports.deleteDocument = async (req, res) => {
    try {
        const document = await Document.findOne({ id: req.params.id });
        if (!document) {
            return res.status(404).json({ msg: 'Document not found' });
        }

        await Document.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Document removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
