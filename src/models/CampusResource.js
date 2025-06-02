const mongoose = require('mongoose');

const CampusResourceSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    name: { type: String, required: true },
    description: { type: String, required: true },
    link: { type: String },
    icon: { type: String }, // Stores icon name or SVG string
    category: {
        type: String,
        required: true,
        enum: ['学术资源', '支持服务', '设施服务', '学生活动']
    }
});

module.exports = mongoose.model('CampusResource', CampusResourceSchema);
