const mongoose = require('mongoose');

const ImportantDateSchema = new mongoose.Schema({
    id: { type: String, required: true, unique: true },
    date: { type: String, required: true },
    description: { type: String, required: true },
    category: {
        type: String,
        required: true,
        enum: ['学术', '假期', '校园活动']
    }
});

module.exports = mongoose.model('ImportantDate', ImportantDateSchema);
