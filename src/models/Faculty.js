const mongoose = require('mongoose');

const FacultySchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    name: { type: String, required: true },
    title: { type: String, required: true },
    department: { type: String, required: true },
    email: { type: String, required: true },
    phone: { type: String },
    office: { type: String },
    bio: { type: String },
    imageUrl: { type: String },
    imageHint: { type: String }
});

module.exports = mongoose.model('Faculty', FacultySchema);
