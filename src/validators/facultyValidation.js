// src/validators/facultyValidation.js
const Joi = require('joi');

const commonFacultySchema = {
    name: Joi.string().min(2).max(100).required(),
    title: Joi.string().min(2).max(100).required(),
    department: Joi.string().min(2).max(100).required(),
    email: Joi.string().email().required(),
    phone: Joi.string().pattern(/^[+\d\s()-]*$/).max(20).allow('', null), // Basic phone pattern
    office: Joi.string().max(50).allow('', null),
    bio: Joi.string().max(1000).allow('', null),
    imageUrl: Joi.string().uri().allow('', null),
    imageHint: Joi.string().max(255).allow('', null)
};

exports.createFacultySchema = Joi.object({
    ...commonFacultySchema
});

exports.updateFacultySchema = Joi.object({
    ...commonFacultySchema,
    name: Joi.string().min(2).max(100),
    title: Joi.string().min(2).max(100),
    department: Joi.string().min(2).max(100),
    email: Joi.string().email()
}).min(1);
