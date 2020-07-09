import databaseActionTypes from 'constants/databaseActionTypes';

const database = (state = {}, action) => {
  switch (action.type) {
    case databaseActionTypes.CLICK_DATABASE_CONNECT:
      // TODO
      console.log("TODO: Connect to database.");
      return {
        ...state,
        connected: true
      };
    default:
      // TODO
      console.log("TODO: Disconnect from database.");
      return {
        ...state,
        connected: false
      };
  }
};

export default database;
