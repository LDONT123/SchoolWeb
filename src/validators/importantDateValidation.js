// src/validators/importantDateValidation.js
const Joi = require('joi');

const commonImportantDateSchema = {
    date: Joi.string().isoDate().required(),
    description: Joi.string().min(5).max(200).required(),
    category: Joi.string().valid('学术', '假期', '校园活动').required()
};

exports.createImportantDateSchema = Joi.object({
    ...commonImportantDateSchema
});

exports.updateImportantDateSchema = Joi.object({
   ...commonImportantDateSchema,
    date: Joi.string().isoDate(),
    description: Joi.string().min(5).max(200),
    category: Joi.string().valid('学术', '假期', '校园活动')
}).min(1);
