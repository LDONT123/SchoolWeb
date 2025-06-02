// src/validators/galleryImageValidation.js
const Joi = require('joi');

const commonGalleryImageSchema = {
    src: Joi.string().uri().required(),
    alt: Joi.string().min(3).max(150).required(),
    caption: Joi.string().max(200).allow('', null),
    dataAiHint: Joi.string().max(255).allow('', null)
};

exports.createGalleryImageSchema = Joi.object({
    ...commonGalleryImageSchema
});

exports.updateGalleryImageSchema = Joi.object({
    ...commonGalleryImageSchema,
    src: Joi.string().uri(),
    alt: Joi.string().min(3).max(150)
}).min(1);
