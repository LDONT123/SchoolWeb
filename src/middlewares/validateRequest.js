// src/middlewares/validateRequest.js
const Joi = require('joi');

const validateRequest = (schema, property = 'body') => {
    return (req, res, next) => {
        const { error } = schema.validate(req[property], { abortEarly: false });
        if (error) {
            const { details } = error;
            const message = details.map(i => i.message.replace(/['"]/g, '')).join(', ');
            // Log the detailed error for server-side inspection
            console.error("Validation Error:", error.details); 
            return res.status(400).json({ error: "Validation failed", details: message });
        }
        next();
    };
};

module.exports = validateRequest;
