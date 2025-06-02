// src/middlewares/errorHandler.js
const errorHandler = (err, req, res, next) => {
    console.error("Global Error Handler Caught:", err); // Log the full error for server-side debugging

    let statusCode = err.statusCode || 500; // Use err.statusCode if available, otherwise default to 500
    let message = err.message || 'Internal Server Error';

    // Handle Mongoose Validation Errors specifically if not already formatted
    if (err.name === 'ValidationError' && !res.headersSent) { // Mongoose validation error
        statusCode = 400;
        // Extract a more user-friendly message if possible
        const errors = Object.values(err.errors).map(el => el.message);
        message = `Validation Failed: ${errors.join(', ')}`;
        // It's better if Joi validation catches this first, but this is a fallback.
    }

    // Handle Mongoose CastErrors (e.g., invalid ObjectId format for _id, though we use string id)
    if (err.name === 'CastError' && err.kind === 'ObjectId' && !res.headersSent) {
        statusCode = 400; // Or 404 if preferred for "not found"
        message = `Invalid ID format: ${err.path} must be a valid identifier.`;
    }
    
    // Handle Mongoose Duplicate Key Error (e.g. code 11000)
    // Note: Controllers already try to check for duplicates, this is another fallback.
    if (err.code === 11000 && !res.headersSent) {
        statusCode = 409; // Conflict
        const field = Object.keys(err.keyValue)[0];
        message = `Duplicate field value: ${field} '${err.keyValue[field]}' already exists.`;
    }


    // Ensure response is sent only if headers haven't been sent yet
    if (!res.headersSent) {
        res.status(statusCode).json({
            error: {
                message: message,
                status: statusCode,
                // Optionally, include stack trace in development
                // stack: process.env.NODE_ENV === 'development' ? err.stack : undefined
            }
        });
    } else {
        // If headers already sent, delegate to the default Express error handler
        // This usually means an error occurred while streaming the response.
        next(err);
    }
};

module.exports = errorHandler;
