const mongoose = require('mongoose');

const NewsSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    title: { type: String, required: true },
    date: { type: String, required: true },
    summary: { type: String, required: true },
    imageUrl: { type: String },
    imageHint: { type: String },
    link: { type: String, required: true }
});

module.exports = mongoose.model('News', NewsSchema);
