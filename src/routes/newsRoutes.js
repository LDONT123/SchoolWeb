// src/routes/newsRoutes.js
const express = require('express');
const router = express.Router();
const newsController = require('../controllers/newsController');
const validateRequest = require('../middlewares/validateRequest');
const { createNewsSchema, updateNewsSchema } = require('../validators/newsValidation');

router.post('/', validateRequest(createNewsSchema), newsController.createNews);
router.get('/', newsController.getAllNews);
router.get('/:id', newsController.getNewsById);
router.put('/:id', validateRequest(updateNewsSchema), newsController.updateNews);
router.delete('/:id', newsController.deleteNews);

module.exports = router;
