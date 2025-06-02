// src/validators/campusResourceValidation.js
const Joi = require('joi');

const commonCampusResourceSchema = {
    name: Joi.string().min(3).max(100).required(),
    description: Joi.string().min(10).required(),
    link: Joi.string().uri().allow('', null),
    icon: Joi.string().max(1000).allow('', null), // Increased length for potential SVG
    category: Joi.string().valid('学术资源', '支持服务', '设施服务', '学生活动').required()
};

exports.createCampusResourceSchema = Joi.object({
    ...commonCampusResourceSchema
});

exports.updateCampusResourceSchema = Joi.object({
    ...commonCampusResourceSchema,
    name: Joi.string().min(3).max(100),
    description: Joi.string().min(10),
    category: Joi.string().valid('学术资源', '支持服务', '设施服务', '学生活动')
}).min(1);
