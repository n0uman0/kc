import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  data: [],
  selected: null,
};

const categoriesSlice = createSlice({
  name: 'categories',
  initialState,
  reducers: {
    setCategories(state, action) {
      state.data = action.payload;
    },
    selectCategory(state, action) {
      state.selected = action.payload;
    },
  },
});

export const { setCategories, selectCategory } = categoriesSlice.actions;
export default categoriesSlice.reducer;
