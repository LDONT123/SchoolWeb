// src/validators/documentValidation.js
const Joi = require('joi');

const commonDocumentSchema = {
    name: Joi.string().min(3).max(100).required(),
    url: Joi.string().uri().required(),
    type: Joi.string().valid('PDF文档', 'Word文档', '链接', '手册', '政策').required(),
    description: Joi.string().max(500).allow('', null)
};

exports.createDocumentSchema = Joi.object({
    ...commonDocumentSchema
});

exports.updateDocumentSchema = Joi.object({
    ...commonDocumentSchema,
    name: Joi.string().min(3).max(100),
    url: Joi.string().uri(),
    type: Joi.string().valid('PDF文档', 'Word文档', '链接', '手册', '政策')
}).min(1);
