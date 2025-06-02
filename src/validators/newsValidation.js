// src/validators/newsValidation.js
const Joi = require('joi');

const commonNewsSchema = {
    title: Joi.string().min(5).max(150).required(),
    date: Joi.string().isoDate().required(),
    summary: Joi.string().min(10).max(500).required(),
    imageUrl: Joi.string().uri().allow('', null),
    imageHint: Joi.string().max(255).allow('', null),
    link: Joi.string().uri().required()
};

exports.createNewsSchema = Joi.object({
    ...commonNewsSchema
});

exports.updateNewsSchema = Joi.object({
    ...commonNewsSchema,
    title: Joi.string().min(5).max(150),
    date: Joi.string().isoDate(),
    summary: Joi.string().min(10).max(500),
    link: Joi.string().uri()
}).min(1);
