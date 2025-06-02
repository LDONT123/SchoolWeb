// src/controllers/galleryImageController.js
const GalleryImage = require('../models/GalleryImage');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/gallery-images
// @desc    Create a gallery image
// @access  Public // Consider admin-only
exports.createGalleryImage = async (req, res) => {
    const { src, alt, caption, dataAiHint } = req.body;
    try {
        // Basic check for duplicates (e.g., same src)
        let image = await GalleryImage.findOne({ src });
        if (image) {
            return res.status(400).json({ errors: [{ msg: 'Gallery image with this source URL already exists' }] });
        }

        image = new GalleryImage({
            id: uuidv4(),
            src,
            alt,
            caption,
            dataAiHint
        });

        await image.save();
        res.status(201).json(image);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/gallery-images
// @desc    Get all gallery images
// @access  Public
exports.getAllGalleryImages = async (req, res) => {
    try {
        const images = await GalleryImage.find().sort({ _id: -1 }); // Sort by insertion order (descending) or choose another field
        res.json(images);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/gallery-images/:id
// @desc    Get gallery image by ID
// @access  Public
exports.getGalleryImageById = async (req, res) => {
    try {
        const image = await GalleryImage.findOne({ id: req.params.id });
        if (!image) {
            return res.status(404).json({ msg: 'Gallery image not found' });
        }
        res.json(image);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/gallery-images/:id
// @desc    Update a gallery image
// @access  Public // Consider admin-only
exports.updateGalleryImage = async (req, res) => {
    const { src, alt, caption, dataAiHint } = req.body;
    const imageFields = {};
    if (src) imageFields.src = src;
    if (alt) imageFields.alt = alt;
    if (caption !== undefined) imageFields.caption = caption;
    if (dataAiHint !== undefined) imageFields.dataAiHint = dataAiHint;

    try {
        let image = await GalleryImage.findOne({ id: req.params.id });
        if (!image) {
            return res.status(404).json({ msg: 'Gallery image not found' });
        }

        // If src is being changed, check if the new src already exists for another image
        if (src && src !== image.src) {
            const existingImage = await GalleryImage.findOne({ src });
            if (existingImage) {
                return res.status(400).json({ errors: [{ msg: 'Another gallery image with this source URL already exists' }] });
            }
        }

        image = await GalleryImage.findOneAndUpdate(
            { id: req.params.id },
            { $set: imageFields },
            { new: true }
        );

        res.json(image);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/gallery-images/:id
// @desc    Delete a gallery image
// @access  Public // Consider admin-only
exports.deleteGalleryImage = async (req, res) => {
    try {
        const image = await GalleryImage.findOne({ id: req.params.id });
        if (!image) {
            return res.status(404).json({ msg: 'Gallery image not found' });
        }

        await GalleryImage.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'Gallery image removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
