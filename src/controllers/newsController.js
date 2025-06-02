// src/controllers/newsController.js
const News = require('../models/News');
const { v4: uuidv4 } = require('uuid');

// @route   POST api/news
// @desc    Create a news item
// @access  Public
exports.createNews = async (req, res) => {
    const { title, date, summary, imageUrl, imageHint, link } = req.body;
    try {
        // Basic check for duplicates based on title and link
        let newsItem = await News.findOne({ title, link });
        if (newsItem) {
            return res.status(400).json({ errors: [{ msg: 'News item with this title and link already exists' }] });
        }

        newsItem = new News({
            id: uuidv4(),
            title,
            date,
            summary,
            imageUrl,
            imageHint,
            link
        });

        await newsItem.save();
        res.status(201).json(newsItem);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/news
// @desc    Get all news items
// @access  Public
exports.getAllNews = async (req, res) => {
    try {
        const page = parseInt(req.query.page, 10) || 1;
        const limit = parseInt(req.query.limit, 10) || 10;
        const sortBy = req.query.sortBy || 'date';
        const order = req.query.order === 'asc' ? 1 : -1;

        const skip = (page - 1) * limit;
        const sortOptions = {};
        if (sortBy) sortOptions[sortBy] = order;

        const newsItems = await News.find()
            .sort(sortOptions)
            .skip(skip)
            .limit(limit);
        
        const totalNews = await News.countDocuments();
        const totalPages = Math.ceil(totalNews / limit);

        res.json({
            data: newsItems,
            pagination: {
                currentPage: page,
                totalPages,
                totalItems: totalNews,
                itemsPerPage: limit
            }
        });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   GET api/news/:id
// @desc    Get news item by ID
// @access  Public
exports.getNewsById = async (req, res) => {
    try {
        const newsItem = await News.findOne({ id: req.params.id });
        if (!newsItem) {
            return res.status(404).json({ msg: 'News item not found' });
        }
        res.json(newsItem);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   PUT api/news/:id
// @desc    Update a news item
// @access  Public
exports.updateNews = async (req, res) => {
    const { title, date, summary, imageUrl, imageHint, link } = req.body;
    const newsFields = {};
    if (title) newsFields.title = title;
    if (date) newsFields.date = date;
    if (summary) newsFields.summary = summary;
    if (link) newsFields.link = link;
    if (imageUrl !== undefined) newsFields.imageUrl = imageUrl;
    if (imageHint !== undefined) newsFields.imageHint = imageHint;

    try {
        let newsItem = await News.findOne({ id: req.params.id });
        if (!newsItem) {
            return res.status(404).json({ msg: 'News item not found' });
        }

        newsItem = await News.findOneAndUpdate(
            { id: req.params.id },
            { $set: newsFields },
            { new: true }
        );

        res.json(newsItem);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};

// @route   DELETE api/news/:id
// @desc    Delete a news item
// @access  Public
exports.deleteNews = async (req, res) => {
    try {
        const newsItem = await News.findOne({ id: req.params.id });
        if (!newsItem) {
            return res.status(404).json({ msg: 'News item not found' });
        }

        await News.findOneAndDelete({ id: req.params.id });

        res.json({ msg: 'News item removed' });
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
};
