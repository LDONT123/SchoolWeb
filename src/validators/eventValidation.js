// src/validators/eventValidation.js
const Joi = require('joi');

const commonEventSchema = {
    title: Joi.string().min(3).max(100).required(),
    date: Joi.string().isoDate().required(), // YYYY-MM-DD
    time: Joi.string().regex(/^([01]\d|2[0-3]):([0-5]\d)$/).allow('', null), // HH:MM or empty/null
    location: Joi.string().min(3).max(100).required(),
    description: Joi.string().min(10).required(),
    imageUrl: Joi.string().uri().allow('', null),
    imageHint: Joi.string().max(255).allow('', null)
};

exports.createEventSchema = Joi.object({
    ...commonEventSchema
    // id is auto-generated, not part of creation schema from client
});

exports.updateEventSchema = Joi.object({
    ...commonEventSchema,
    title: Joi.string().min(3).max(100), // Make fields optional for update
    date: Joi.string().isoDate(),
    location: Joi.string().min(3).max(100),
    description: Joi.string().min(10)
}).min(1); // At least one field must be present for an update
