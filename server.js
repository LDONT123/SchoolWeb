// server.js
const express = require('express');
const connectDB = require('./src/config/db');
const errorHandler = require('./src/middlewares/errorHandler'); // Import error handler

const app = express();

// Connect Database
connectDB();

// Init Middleware
app.use(express.json({ extended: false }));

app.get('/', (req, res) => res.send('Hello World! API Running!'));

// Define Routes
app.use('/api/events', require('./src/routes/eventRoutes'));
app.use('/api/news', require('./src/routes/newsRoutes'));
app.use('/api/important-dates', require('./src/routes/importantDateRoutes'));
app.use('/api/faculty', require('./src/routes/facultyRoutes'));
app.use('/api/campus-resources', require('./src/routes/campusResourceRoutes'));
app.use('/api/gallery-images', require('./src/routes/galleryImageRoutes'));
app.use('/api/documents', require('./src/routes/documentRoutes'));

// Global Error Handler - Must be last middleware
app.use(errorHandler);

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => console.log(`Server started on port ${PORT}`));
