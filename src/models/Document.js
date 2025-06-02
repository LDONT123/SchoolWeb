const mongoose = require('mongoose');

const DocumentSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    name: { type: String, required: true },
    url: { type: String, required: true },
    type: {
        type: String,
        required: true,
        enum: ['PDF文档', 'Word文档', '链接', '手册', '政策']
    },
    description: { type: String }
});

module.exports = mongoose.model('Document', DocumentSchema);
