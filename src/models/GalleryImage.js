const mongoose = require('mongoose');

const GalleryImageSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    src: { type: String, required: true },
    alt: { type: String, required: true },
    caption: { type: String },
    dataAiHint: { type: String }
});

module.exports = mongoose.model('GalleryImage', GalleryImageSchema);
