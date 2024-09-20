import React, { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { setCategories } from './features/categoriesSlice';
import { setCourses } from './features/coursesSlice';
import CategoryMenu from './components/CategoryMenu';
import CourseList from './components/CourseList';

const App = () => {
  const dispatch = useDispatch();

  useEffect(() => {
    
    const fetchCategories = async () => {
      const categories = await fetch('http://api.cc.localhost/categories').then(res => res.json());
      dispatch(setCategories(categories));
    };

    const fetchCourses = async () => {
      const courses = await fetch('http://api.cc.localhost/courses').then(res => res.json());
      dispatch(setCourses(courses));
    };

    fetchCategories();
    fetchCourses();
  }, [dispatch]);

  return (
    <div className="container mx-auto p-8 flex">
      <CategoryMenu />
      <CourseList />
    </div>
  );
};

export default App;
