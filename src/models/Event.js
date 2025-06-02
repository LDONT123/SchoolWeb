const mongoose = require('mongoose');

const EventSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    title: { type: String, required: true },
    date: { type: String, required: true },
    time: { type: String },
    location: { type: String, required: true },
    description: { type: String, required: true },
    imageUrl: { type: String },
    imageHint: { type: String }
});

module.exports = mongoose.model('Event', EventSchema);
