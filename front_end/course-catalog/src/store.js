import { configureStore } from '@reduxjs/toolkit';
import categoriesReducer from './features/categoriesSlice';
import coursesReducer from './features/coursesSlice';

export const store = configureStore({
  reducer: {
    categories: categoriesReducer,
    courses: coursesReducer,
  },
});

export default store;